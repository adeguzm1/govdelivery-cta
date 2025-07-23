import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('GovDelivery Settings', 'govdelivery-cta')}>
                    <SelectControl
                        label={__('Action Type', 'govdelivery-cta')}
                        value={attributes.actionType}
                        options={[
                            { label: __('Show topic list', 'govdelivery-cta'), value: 'account' },
                            { label: __('Subscribe to specific topic', 'govdelivery-cta'), value: 'topic' },
                        ]}
                        onChange={(value) => setAttributes({ actionType: value })}
                    />
                    {attributes.actionType === 'topic' && (
                        <TextControl
                            label={__('Topic ID', 'govdelivery-cta')}
                            value={attributes.topicId}
                            onChange={(value) => setAttributes({ topicId: value })}
                        />
                    )}
                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                <TextControl
                    label={__('CTA Title', 'govdelivery-cta')}
                    value={attributes.title}
                    onChange={(value) => setAttributes({ title: value })}
                />
                <TextControl
                    label={__('CTA Description', 'govdelivery-cta')}
                    value={attributes.description}
                    onChange={(value) => setAttributes({ description: value })}
                />
                <TextControl
                    label={__('Button Text', 'govdelivery-cta')}
                    value={attributes.buttonText}
                    onChange={(value) => setAttributes({ buttonText: value })}
                />
            </div>
        </>
    );
}
